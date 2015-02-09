#!/bin/bash
# Script to update the jobs
# Version: 0.0.1alpha
# Options: user-id

# Check if SLURM present and get the running jobs
if [ -z "$(which squeue)" ]; then
   slurm=false
else
   slurm_jobs=$(squeue -h -A $USER)
   slurm=true
fi

origin=$PWD
updated_jobs=""

proc=$(ps -u $USER -o comm)

notRunning() {
  local job_id="wcf_$1"

  # Check in the SLURM queue
  if $slurm; then
    case "$slurm_jobs" in
      *"$job_id"* ) local is_slurm=true ;;
      * ) local is_slurm=false ;;
    esac
  else
    local is_slurm=false
  fi

  # Check in the ps tree
  case "$proc" in
    *"$job_id"* ) local is_ps=true ;;
    * ) local is_ps=false ;;     
  esac

  if  $is_slurm ||  $is_ps; then 
    return 1
  else
    return 0
  fi
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
        updated_jobs+=" $job_id"
        rm -fr $elem
      fi 
    fi
  fi
done

if [ -z "$updated_jobs" ]; then
  echo None
else
  echo $updated_jobs
fi
