#!/bin/bash

set -euo pipefail

GIT_DIR="/tmp/plus-publish-gh-pages"
WORKER_DIR=$(pwd)

if [ -d "$GIT_DIR" ]; then
    rm -rf "$GIT_DIR"
fi;

npm run docs:build
mkdir "$GIT_DIR"
cd "$GIT_DIR"

GITBRANCHDATA=$(curl https://api.github.com/repos/slimkit/plus/branches)
if echo "$GITBRANCHDATA" | grep -q "gh-pages"
then
    git clone --branch=gh-pages https://github.com/slimkit/plus "$GIT_DIR"
else
    git clone --branch=master https://github.com/slimkit/plus "$GIT_DIR"
    git checkout --orphan gh-pages
    git reset --hard
fi

# Copy dist
cp -R "$WORKER_DIR/docs/.vuepress/dist/" "$GIT_DIR/"
git add .
git commit -m "deploy"

if [ "@$@" != "@bot" ]; then
    git push --set-upstream origin gh-pages
else
    rm -rf ./.git/
fi;

cd -
