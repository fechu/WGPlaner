#!/bin/bash

# Generate ctags for vendor folder
ctags -f vendor.tags \
    -R \
    --totals=yes \
    --fields=+aimS \
    vendor/

# Generate ctags for the rest of the project.
ctags -f tags \
    -R \
    --verbose=yes \
    --totals=yes \
    --fields=+aimS \
    module/
