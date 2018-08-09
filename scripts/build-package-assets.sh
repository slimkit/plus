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

# build groups
cd packages/zhiyicx-plus-group/
yarn
yarn prod
cd -

# build questions
cd packages/zhiyicx-plus-question/
yarn
yarn prod
cd -
