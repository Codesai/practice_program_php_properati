.PHONY: build

build:
	docker run --rm --interactive --tty --volume $$PWD:/app composer install

test:
	docker run --rm --interactive --tty --volume $$PWD:/app composer exec phpunit

mutation-test:
	docker run --rm --interactive --tty --volume $$PWD:/app composer exec infection
