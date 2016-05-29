#!/bin/sh
setterm -term linux -back black -fore red -clear

RED='\033[0;31m'
NC='\033[0m' 
BLUE='\033[1;34m'
NC='\033[0m' 

DIRFILE=`readlink -e "$0"`
CURFILE=`basename "$DIRFILE"`
CURDIR=`dirname "$DIRFILE"`

echo "\n"
echo "${RED} Devoleped by LARocca${NC}"
echo " Email: yaeriks@yandex.com"
echo "${BLUE} Skype: larocca2012${NC}"
sleep 2
setterm -term linux -back black -fore cyan -clear
while (true) 
do
php -q -f $CURDIR/w.php 
done;
