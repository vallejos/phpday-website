Vagrant.configure("2") do |config|
    # small vagrant just for testing ansible scripts

    config.vm.provider :virtualbox do |v|
        v.name = "phpday"
    end

    config.vm.box = "deb/wheezy-amd64"

    config.vm.network :private_network, ip: "192.168.33.91"
    config.ssh.forward_agent = true

    # config.vm.synced_folder "./", "/vagrant", type: "nfs"
end
