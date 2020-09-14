# st-demo

## Начальная сборка необходимых образов

```
cd %path_to_project% 

docker-compose up -d --build
```

Тестовый запрос к домашней страницы

```
curl -c cookies.txt -b cookies.txt http://localhost
```

Запрос для регистрации пользователя

```
curl --request POST \
  --url http://localhost/registration \
  --location \
  --cookie-jar cookies.txt --cookie cookies.txt \
  --header 'content-type: multipart/form-data;' \
  --form nickname=asdf \
  --form password=asdf \
  --form lastname=asdf \
  --form firstname=asdf \
  --form age=11
```


Запрос для входа в систему

```
curl --request POST \
  --url http://localhost/login \
  --location \
  --cookie-jar cookies.txt --cookie cookies.txt \
  --header 'content-type: multipart/form-data;' \
  --form nickname=asdf \
  --form password=asdf
```

Отслеживание любого события, необходимо подставить любое значение в запрос http://localhost/t/%source_label% где %source_label% -> ~\w+~

```
curl --request GET \
  --url http://localhost/t/hello_world \
  --cookie-jar cookies.txt --cookie cookies.txt
```

"База данных" в файлах хранится в файловой системе контейнеров service:/path/into/container/:
 - web:/app/var/user-storage - зарегистрированные пользователи
 - worker-track-handler:/app/var/analytics-storage - данные собранные через mc Analytics
 
 ### Сброс данных
 
```
docker-compose down
```