# Vagrant configuration for LAMP stack

Vagrant.configure(2) do |config|
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "hashicorp/precise32"

  # Run bootstrap script which installs different things
  config.vm.provision :shell, path: "vagrant_bootstrap.sh"

  # Bind ports 
  config.vm.network :forwarded_port, host:8080, guest: 80

end

