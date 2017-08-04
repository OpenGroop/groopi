#!/bin/bash

# setjog_here.sh
#
# place it in ~/public_html/lib/jpgraph and launch it
# it'll set the permissions recursively

find . -type d -print0 | xargs -0 chmod 755
find . -type f -print0 | xargs -0 chmod 644
chmod 755 .
chmod u+x $0
