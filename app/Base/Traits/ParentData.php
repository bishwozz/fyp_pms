<?php
namespace App\Base\Traits;

use ReflectionClass;


/**
 * 
 */
trait ParentData
{
    

    public function parent($name)
    {
        $request = $this->request;
        return $request->route($name) ?? null;
    }

    public function setUpLinks($methods = ['index'])
    {
        $currentMethod = $this->crud->getActionMethod();
        $exits = method_exists($this, 'tabLinks');
        if ($exits && in_array($currentMethod, $methods)) {
            $this->data['tab_links'] = $this->tabLinks();
        }
    }

    public function enableDialog( $enable = true)
    {
        $this->data['controller'] = (new \ReflectionClass($this))->getShortName();
        $this->crud->controller = $this->data['controller'];
        $this->enableDialog = $enable;
        $this->data['enableDialog'] = property_exists($this, 'enableDialog') ? $this->enableDialog : false;
        $this->crud->enableDialog = property_exists($this, 'enableDialog') ? $this->enableDialog : false;
    }

}
