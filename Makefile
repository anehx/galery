run:
	@docker-compose up

shell:
	@docker exec -it galery_web_1 bash

db-shell:
	@mysql -h 127.0.0.1 -P 3306 -u galery -pgalery galery

init-db:
	@mysql -h 127.0.0.1 -P 3306 -u galery -pgalery galery < tools/sql/schema.sql
	@mysql -h 127.0.0.1 -P 3306 -u galery -pgalery galery < tools/sql/data.sql
