#!/bin/bash



curl -Ls -o /dev/null -w "%{url_effective}\n" $1



# curl télécharge toujours quelque chose (HTML, redirection, etc.) si on ne veut pas on redirige dans dev null , avec -o output file
#-L Follow redirects Si le site redirige (bit.ly → autre site), suis la redirection -s  N’affiche pas les messages inutiles (progression, erreurs visibles) -L + -s = -Ls
# -w = write-out en gros ecrire ca a la fin en gros .Ex :  -w "\n"