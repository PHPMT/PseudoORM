# -*- mode: ruby -*-
# vi: set ft=ruby :

github_username       = "fideloper"
github_repo           = "Vaprobash"
github_branch         = "1.4.0"
github_url            = "https://raw.githubusercontent.com/#{github_username}/#{github_repo}/#{github_branch}"
hostname              = "pseudo-orm.local"
server_ip             = "192.168.22.101"
server_cpus           = "1"
server_memory         = "384"
server_swap           = "768"
server_timezone       = "UTC"
pgsql_root_password   = "root"
php_timezone          = "UTC"
php_version           = "5.6"
hhvm                  = "false"
public_folder         = "/vagrant"

Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/trusty64"
  config.vm.define "PseudoORM" do |vapro|
  end
  if Vagrant.has_plugin?("vagrant-hostmanager")
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.include_offline = false
  end
  config.vm.hostname = hostname
  config.vm.network :private_network, ip: server_ip
  config.vm.network :forwarded_port, guest: 80, host: 8000
  config.ssh.forward_agent = true
  config.vm.synced_folder ".", "/vagrant",
            id: "core",
            :nfs => true,
            :mount_options => ['nolock,vers=3,udp,noatime']
  if File.file?(File.expand_path("~/.gitconfig"))
    config.vm.provision "file", source: "~/.gitconfig", destination: ".gitconfig"
  end
  config.vm.provider :virtualbox do |vb|
    vb.name = "PseudoORM"
    vb.customize ["modifyvm", :id, "--cpus", server_cpus]
    vb.customize ["modifyvm", :id, "--memory", server_memory]
    vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000]
  end
  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :box
    config.cache.synced_folder_opts = {
        type: :nfs,
        mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }
  end

  config.vm.provision "shell", path: "#{github_url}/scripts/base.sh", args: [github_url, server_swap, server_timezone]
  config.vm.provision "shell", path: "#{github_url}/scripts/base_box_optimizations.sh", privileged: true
  config.vm.provision "shell", path: "#{github_url}/scripts/php.sh", args: [php_timezone, hhvm, php_version]
  config.vm.provision "shell", path: "#{github_url}/scripts/vim.sh", args: github_url
  config.vm.provision "shell", path: "#{github_url}/scripts/nginx.sh", args: [server_ip, public_folder, hostname, github_url]
  config.vm.provision "shell", path: "#{github_url}/scripts/pgsql.sh", args: pgsql_root_password

end
