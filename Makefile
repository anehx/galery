run:
	@docker-compose up
	@firefox http://localhost:8080

docker-bash:
	@docker exec -it galery_web_1 bash
