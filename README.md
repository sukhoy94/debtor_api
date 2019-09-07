# debtor_api
api for debtor application

INSTRUCTION FOR LOCAL INSTALLATION

1. install composer https://getcomposer.org/download/
2. in project root folder: composer install
3. Install vagrant: https://www.vagrantup.com/downloads.html
4. In project root make homestead.yaml file 
vendor\bin\homestead make

############ SAMPLE Homestead.yaml file ######################

ip: 192.168.10.10 # if you use multiple vm simultaneously change the ip
memory: 2048
cpus: 2
provider: virtualbox
authorize: ~/.ssh/id_rsa.pub
keys:
    - ~/.ssh/id_rsa
folders:
    -
        map: 'D:\coding\debtor\debtor_api' # project folder
        to: /home/vagrant/code
sites:
    -
        map: debtor_api.test  # add this to available hosts 
        to: /home/vagrant/code/public
databases:
    - homestead
features:
    -
        mariadb: false
    -
        ohmyzsh: false
    -
        webdriver: false
name: debtor-api
hostname: debtor-api


###############################################################

5. run: vagrant up
6. application url: debtor_api.test
