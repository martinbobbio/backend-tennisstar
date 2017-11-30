<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            ->server('user@hostname')
            ->deployDir('/public_html/admin-tenis')
            ->repositoryUrl('git@github.com:martinbobbio/davinci-tennisstar-backend.git')
            ->repositoryBranch('master')
        ;
    }
};