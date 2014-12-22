# Vagrant configuration for LAMP stack

Vagrant.configure(2) do |config|
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "hashicorp/precise32"

  # Run bootstrap script which installs different things
  config.vm.provision :shell, path: "vagrant_bootstrap.sh"

  # Private Network is needed for NFS file sharing
  config.vm.network "private_network", ip: "192.168.0.99"

  # Bind ports 
  config.vm.network :forwarded_port, host:8080, guest: 80

  # Use NFS file system. Default shared system of Virtualbox is very slow.
  config.vm.synced_folder ".", "/vagrant", type: "nfs"

end

