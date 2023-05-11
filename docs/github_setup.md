# Github configure

* Set default github username and email
```shell
git config --global user.name "sirharis214"
git config --global user.email "hariskido214@gmail.com"
```

* Update the file: `~/.gitconfig` so we don't have provide PAT with every git command to remote
```shell
[user]
	name = x
	email = x@x.com
[credential]
	helper = store
```

* Create file ~/.git-credentials
```shell
https://<username>:<PAT>@github.com
```

