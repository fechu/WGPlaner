#!/bin/sh 

tmux new-session -s "WGPlaner" -d 
tmux split-window -h -c 'module/Application/test/' 
tmux -2 attach-session -t "WGPlaner"
