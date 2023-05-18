#!/bin/bash

clear
echo -e "Realizando commit do respositório."
git add .
git commit -m "$1"
eval "$(ssh-agent -s)" && ssh-add ~/.ssh/analytics
git push -u origin $2
