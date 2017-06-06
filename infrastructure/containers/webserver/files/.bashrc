# Default shell
export SHELL=/bin/bash

# ls alias with colors
export LS_OPTIONS='--color=auto'
eval "`dircolors`"
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -l'
alias l='ls $LS_OPTIONS -lA'

# PS1
DEFAULT_PS1_COLOR="\[\033[01;38;5;100m\]"
PS1_DATE="\[\033[00;38;5;100m\]\t$DEFAULT_PS1_COLOR"
PS1_HOST_STRING="\[\033[01;38;5;208m\]\u@\[\033[01;38;5;208m\]\h$DEFAULT_PS1_COLOR"
PS1_PATH="\[\033[01;38;5;178m\][\w]$DEFAULT_PS1_COLOR"
PS1_COLOR_RESET="\[\033[00;38;5;255m\]"
PS1_EXEC_LINE="$DEFAULT_PS1_COLOR# $PS1_COLOR_RESET"

function get_last_command_failed_ps1()
{
  # It has to be the first command, otherwise it will get the 0 from the previous one
  EXIT_CODE="$?"
  if [ "$EXIT_CODE" -ne "0" ] && [ "$EXIT_CODE" -ne 130 ]; then
    PS1_FAILURE_COLOR="\\033[01;38;5;220m\\033[48;5;196m"
    PS1_COLOR_RESET="\\033[00;38;5;255m"
    echo -ne " ${PS1_FAILURE_COLOR} ! ${PS1_COLOR_RESET} "
  fi
}

PS1='$(get_last_command_failed_ps1)'$PS1_DATE\ $PS1_HOST_STRING:\ $PS1_PATH\\n$PS1_EXEC_LINE$PS1_COLOR_RESET
