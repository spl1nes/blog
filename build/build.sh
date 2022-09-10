#!/bin/bash

echo "#################################################"
echo "Declare config"
echo "#################################################"
echo ""

# Paths
BASE_PATH="/var/www/html"
ROOT_PATH="/var/www/html/blog"
BUILD_PATH="/var/www/html/blog/build/bin"

mkdir -p ${BUILD_PATH}
mkdir -p ${BUILD_PATH}/download

echo ""
echo "#################################################"
echo "Update blog"
echo "#################################################"
echo ""

cd ${ROOT_PATH}

git reset --hard
git pull

echo "#################################################"
echo "Remove old setup"
echo "#################################################"
echo ""

# Previous cleanup
rm -r -f ${BUILD_PATH}/*

echo "#################################################"
echo "Setup repositories"
echo "#################################################"
echo ""

cd ${BUILD_PATH}

git clone -b master https://github.com/Karaka-Management/Organization-Guide.git >/dev/null
git clone -b master https://github.com/Karaka-Management/OCRImageOptimizerApp.git >/dev/null
git clone -b master https://github.com/Karaka-Management/TestReportGenerator.git >/dev/null
git clone -b master https://github.com/Karaka-Management/WordLinkValidatorApp.git >/dev/null
git clone -b master https://github.com/Karaka-Management/DocumentComparisonApp.git >/dev/null
git clone -b master https://github.com/Karaka-Management/CombinatoricsSolverApp.git >/dev/null

echo "#################################################"
echo "Build projects"
echo "#################################################"
echo ""

# Org Guide
zip -r ${BUILD_PATH}/download/Organization_Guide.zip ${BUILD_PATH}/Organization-Guide

# OCRImageOptimizer
zip ${BUILD_PATH}/download/OCRImageOptimizerApp_Demo.zip ${BUILD_PATH}/OCRImageOptimizerApp/README.md ${BUILD_PATH}/OCRImageOptimizerApp/bin/x64/Demo/OCRImageOptimizerApp.exe
zip ${BUILD_PATH}/download/OCRImageOptimizerApp.zip ${BUILD_PATH}/OCRImageOptimizerApp/README.md ${BUILD_PATH}/OCRImageOptimizerApp/bin/x64/Release/OCRImageOptimizerApp.exe

# TestReportGenerator.git
php ${BUILD_PATH}/TestReportGenerator/build_phar.php
zip ${BUILD_PATH}/download/TestReportGenerator.zip ${BUILD_PATH}/TestReportGenerator/README.md ${BUILD_PATH}/TestReportGenerator/testreportgenerator.phar
rm ${BUILD_PATH}/TestReportGenerator/testreportgenerator.phar

# WordLinkValidator
zip ${BUILD_PATH}/download/WordLinkValidatorApp_Demo.zip ${BUILD_PATH}/WordLinkValidatorApp/README.md ${BUILD_PATH}/WordLinkValidatorApp/bin/Demo/net6.0-windows/
zip ${BUILD_PATH}/download/WordLinkValidatorApp.zip ${BUILD_PATH}/WordLinkValidatorApp/README.md ${BUILD_PATH}/WordLinkValidatorApp/bin/Release/net6.0-windows/

# DocumentComparison
zip ${BUILD_PATH}/download/DocumentComparisonApp_Demo.zip ${BUILD_PATH}/DocumentComparisonApp/README.md ${BUILD_PATH}/DocumentComparisonApp/bin/Demo/net6.0-windows/
zip ${BUILD_PATH}/download/DocumentComparisonApp.zip ${BUILD_PATH}/DocumentComparisonApp/README.md ${BUILD_PATH}/DocumentComparisonApp/bin/Release/net6.0-windows/

# CombinatoricsSolver
zip ${BUILD_PATH}/download/CombinatoricsSolverApp_Demo.zip ${BUILD_PATH}/CombinatoricsSolverApp/README.md ${BUILD_PATH}/CombinatoricsSolverApp/bin/Demo/net6.0-windows/
zip ${BUILD_PATH}/download/CombinatoricsSolverApp.zip ${BUILD_PATH}/CombinatoricsSolverApp/README.md ${BUILD_PATH}/CombinatoricsSolverApp/bin/Release/net6.0-windows/

cd ${BASE_PATH}

echo ""