install:
	@make generate_key
	@cd ./images/app && docker build -t core-app . && cd ../../
	@make run

uninstall:
	@make stop
	@docker rm core-app-container
	@docker image rm core-app

run:
	@docker run --name core-app-container -p 8080:8080 -v ./conf.d:/conf.d:ro -e KEY_PASSPHRASE=qweqweasd -d core-app

stop:
	@docker stop core-app-container

version_up:
	@echo version up

version_down:
	@echo version down

generate_key:
	@ssh-keygen -t rsa -m pem -f ./images/app/volume/cert/key.pem
