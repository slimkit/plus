#!/bin/bash

set -euo pipefail

# Build master
yarn
yarn production

# Build Feed
cd packages/slimkit-plus-feed/
yarn
yarn build
cd -

# build news
cd packages/slimkit-plus-news/
yarn
yarn dist
cd -
