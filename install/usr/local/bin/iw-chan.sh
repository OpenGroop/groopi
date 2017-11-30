#!/bin/bash

line=$(iwlist wlan0 scan essid $1 | grep Channel: )

#echo $line
#echo ${#line}

channel=''
if [[ ${#line} -gt 0 ]]
then
    for (( i=0; i<${#line}; i++)); do
        if [[ ${line:$i:1} == ':' ]]
        then
            for (( j=(($i+1)); j<${#line}; j++)); do
                channel=$channel${line:$j:1}
            done
        fi
    done
else
    channel=0
fi

echo $channel
#line=""

exit 0


