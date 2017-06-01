# Robinzhao / Image Optimizer

    This is a Wordpress plugin to auto optimize uploaded images with Imagemagick.

    Use at your own risk!

## Requiremenet

    Install Imagemagick on your server

## Description

    mogrify -strip -interlace Plane -quality 85% -resize '768x432>' -sampling-factor 4:2:0 your-image.jpg
