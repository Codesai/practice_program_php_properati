.PHONY: build

build:
	docker run --rm --interactive --tty --volume $$PWD:/app composer install

test:
	docker run --rm --interactive --tty --volume $$PWD:/app composer exec phpunit

coverage:
	docker run --rm --interactive --tty --volume $$PWD:/app composer exec "phpunit --coverage-text"

mutation-test:
	docker run --rm --interactive --tty --volume $$PWD:/app composer exec infection
