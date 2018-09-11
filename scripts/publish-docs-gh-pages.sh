#!/bin/bash

set -euo pipefail

WORKER_DIR=$(pwd)
GIT_DIR="$WORKER_DIR/storage/app/plus-publish-gh-pages"

if [ -d "$GIT_DIR" ]; then
    rm -rf "$GIT_DIR"
fi;

yarn docs:build
mkdir "$GIT_DIR"
cd "$GIT_DIR"

if [ "@$@" != "@bot" ]; then
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
    git push --set-upstream origin gh-pages
else
    cp -R "$WORKER_DIR/docs/.vuepress/dist/" "$GIT_DIR/"
    ls "$GIT_DIR"
fi

cd -
