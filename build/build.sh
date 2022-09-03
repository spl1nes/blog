#!/bin/bash

echo "#################################################"
echo "Declare config"
echo "#################################################"

# Paths
BASE_PATH="/var/www/html"
ROOT_PATH="/var/www/html/blog"
BUILD_PATH="/var/www/html/blog/build/bin"

mkdir -p ${BASE_PATH}

echo "#################################################"
echo "Update blog"
echo "#################################################"

cd ${ROOT_PATH}

git reset --hard
git pull

echo "#################################################"
echo "Remove old setup"
echo "#################################################"

# Previous cleanup
rm -r -f ${BUILD_PATH}/*

echo "#################################################"
echo "Setup repositories"
echo "#################################################"

cd ${BUILD_PATH}

git clone -b master https://github.com/Karaka-Management/Organization-Guide.git >/dev/null
git clone -b master https://github.com/Karaka-Management/OCRImageOptimizerApp.git >/dev/null
git clone -b master https://github.com/Karaka-Management/TestReportGenerator.git >/dev/null

echo "#################################################"
echo "Build projects"
echo "#################################################"

cd ${BASE_PATH}