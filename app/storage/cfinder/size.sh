#!/bin/bash

# Filter out comments and empty lines and create a temp file
grep -v ^[[:space:]]*$ "$1" | grep -v ^# > /tmp/tmp$$

edges=$(wc -l /tmp/tmp$$)

for word in $(cat /tmp/tmp$$)
do
  echo "$word" >> /tmp/out$$
done

nodes=$(sort -f /tmp/out$$ | uniq -i | wc -l)

echo $nodes $edges
rm /tmp/tmp$$ /tmp/out$$

