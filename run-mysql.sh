docker run -d \
  --name mysql-container \
  -e MYSQL_ROOT_PASSWORD=secret \
  -e MYSQL_DATABASE=faktur_generator \
  -e MYSQL_USER=user \
  -e MYSQL_PASSWORD=secret \
  -p 3306:3306 \
  mysql:latest
