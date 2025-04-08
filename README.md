![MVC Image](public/img/mvc.png)

## Klona repot till github
To clone this course repo to github we have to follow the following stuff

- Add files with `git add <file> or .`
- Commit them with `git commit -m "medelandet"`
- Uppload them with `git push`
- Add tag by `git tag -a v1.0.0 -m "First version"`
- Check tag `git tag`
- Upload tag or tags `git push --tags`

## Hur kommer man igång med att köra webplatsen
- To start the webbpage you go to ubuntu
- You navigate to courese repo in this case `dbwebb-kurser/mvc/me/report`
- To start the server you run the command ` php -S localhost:8888 -t public  ` which starts the server
- Then you are ready to access the webbpage
- Mind you that after any changes in CSS or adding an images you have to `npm run build` so that your changes can be uppdated to publik