#!/bin/sh 

tmux new-session -s "WGPlaner" -d 
tmux split-window -h -c 'module/Application/test/' -d
tmux split-window -v -d "vagrant ssh"
tmux -2 attach-session -t "WGPlaner"
