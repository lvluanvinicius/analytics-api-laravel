#!/bin/bash

clear
echo -e "\nRealizando commit do respositório.\n"
git add .
git commit -m "$1"
eval "$(ssh-agent -s)" && ssh-add ~/.ssh/analytics
git push -u origin $2
