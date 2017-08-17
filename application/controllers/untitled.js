// dependencies
var async = require('async');
var path = require('path');
var AWS = require('aws-sdk');

var gm = require('gm')
            .subClass({ imageMagick: true }); // Enable ImageMagick integration.
var util = require('util');

// constants


// get reference to S3 client
var s3 = new AWS.S3({signatureVersion: 'v4'});
 
  
 
exports.handler = function(event, context) {
  // Read options from the event.

  var res_obj = event.Records[0];
  console.log(res_obj);
 // console.log("Reading options from event:\n", util.inspect(event, {depth: 5}));
  var srcBucket = res_obj.s3.bucket.name;
  console.log(srcBucket);
  // Object key may have spaces or unicode non-ASCII characters.
    var srcKey    =
    decodeURIComponent(res_obj.s3.object.key.replace(/\+/g, " "));
  var dstBucket = "retail-safari";
  var dstKey    = srcKey;
  var extension = path.extname(dstKey);
  var filename  = path.basename(dstKey, extension);
  console.log(filename);
  var arr = filename.split("_");
  console.log(arr[0]);
  if(arr[0]=='large'){
    var MAX_WIDTH  = 300;
    var MAX_HEIGHT = 300;
  }
  else{
    var MAX_WIDTH  = 80;
    var MAX_HEIGHT = 80;
  }
  var directory = "resize_image";
  dstKey = directory + '/' + filename + extension;

  console.log('Dumping resized file to: ' + dstKey);

  // Infer the image type.
  var typeMatch = srcKey.match(/\.([^.]*)$/);
  if (!typeMatch) {
    console.error('unable to infer image type for key ' + srcKey);
    return;
  }
  var imageType = typeMatch[1];
  if (imageType != "jpg" && imageType != "jpeg"  && imageType != "png" && imageType != "gif" && imageType != "bmp"  ) {
    console.log('skipping non-image ' + srcKey);
    return;
  }

  // Download the image from S3, transform, and upload to a different S3 bucket.
  async.waterfall([
    function download(next) {
      // Download the image from S3 into a buffer.
      s3.getObject({
          Bucket: srcBucket,
          Key: srcKey
        },
        next);
      },
    function transform(response, next) {
      gm(response.Body).size(function(err, size) {
// If correct size then cancel all ops        
if(size.width <= MAX_WIDTH && size.height <= MAX_HEIGHT){
	console.log(" No need to resize");
	return;
}


// Infer the scaling factor to avoid stretching the image unnaturally.
        var scalingFactor = Math.min(
          MAX_WIDTH / size.width,
          MAX_HEIGHT / size.height
        );
        var width  = scalingFactor * size.width;
        var height = scalingFactor * size.height;
        // var height = scalingFactor * size.height;

        // Transform the image buffer in memory.
        this.resize(width, height)
          .toBuffer(imageType, function(err, buffer) {
            if (err) {
                console.log(err);
              next(err);
            } else {
              next(null, response.ContentType, buffer);
            }
          });
      });
    },
    function upload(contentType, data, next) {
      // Stream the transformed image to a different S3 bucket.
      s3.putObject({
          Bucket: dstBucket,
          Key: dstKey,
          Body: data,
          ContentType: contentType,
	  ACL: 'public-read',
	  CacheControl: 'max-age=2592000'
        },
        next);
      }
    ], function (err) {
      if (err) {
        console.error(
          'Unable to resize ' + srcBucket + '/' + srcKey +
          ' and upload to ' + dstBucket + '/' + dstKey +
          ' due to an error: ' + err
        );
      } else {
        console.log(
          'Successfully resized ' + srcBucket + '/' + srcKey +
          ' and uploaded to ' + dstBucket + '/' + dstKey
        );
      }

      context.done();
    }
  );
};
