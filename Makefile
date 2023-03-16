default: help

NAMESPACE="code-standards"
SHELL=/bin/bash

.PHONY: *

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo "Available commands:"
	@grep -v '^_' Makefile | grep '^[^#[:space:]].*:' | grep -v '^default' | sed 's/:\(.*\)//' | xargs -n 1 echo ' -'

code-standards:
	vendor/bin/php-cs-fixer fix --verbose