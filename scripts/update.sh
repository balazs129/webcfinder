#!/bin/bash
# Script to get the status of remote jobs
# Version: 0.0.2alpha
# Options: user-id

if [ -z "$1" ]; then
    echo "Please give an user directory name!"
    exit
fi

origin=$PWD
updated_jobs=""
running_jobs=$(squeue -h -u balazs129 -o "%j")

notRunning() {
  local job_id="wcf_$1"

  case "$running_jobs" in
      *"$job_id"* ) return 1 ;;
      * ) return 0 ;;
  esac
}

for elem in $1/*
do
  if [ -d $elem ]; then
    job_id=${elem##*/}
    if notRunning $job_id; then
      result_dir=$elem/*_files
      if [ -d $result_dir ]; then
        result_file=result_$job_id.tar.gz
        # TODO: pack in a result dir
        cd $result_dir
        tar czf ../../$result_file .
        cd $origin
        updated_jobs+=" $job_id:OK"
        rm -fr $elem
      else
        updated_jobs+=" $job_id:FAILED" 
      fi
    fi
  fi
done

if [ -z "$updated_jobs" ]; then
  echo None
else
  echo $updated_jobs
fi

