<?php namespace SCM\Classes;

use SCM\Model\Settings;
use SCM\Controller\MainPageController as MainPageController;

class RouterException extends \Exception{};

class Router {

    const ACMBaseRoute = SCM_ROUTE_HANDLE;

    /**
     * the route actions to be excempted in token verification
     *
     * @var array
     */
    protected $csrfExemptedActions = array();

    /**
     * the main SCM route, whether to trigger all stuffs here or not
     *
     * @var
     */
    protected $baseRoute;

    /**
     * add action to be exempted in token verification
     *
     * @param array $actions
     */
    public function addCSRFExemptedActions($actions = array())
    {
        $this->csrfExemptedActions = $actions;
    }


    /**
     * boots the routing and process routes
     *
     * @throws RouterException
     */
    public function boot()
    {

        $this->baseRoute = SCMUtility::cleanText( (isset($_GET['page'])) ? $_GET['page'] : '' );

        if( $this->isBaseRouteIsACM() )
        {

            if( $this->isStatePresentInQueryString() )
            {

                $controllerClass = $this->buildControllerClassName($this->getStateParamValue());

                if(class_exists($controllerClass))
                {

                    $controllerInstance = new $controllerClass();

                    if( ! is_callable(array($controllerInstance,$this->getActionParamValue())))
                    {

                        if(Settings::isDebugMode())
                        {
                            throw new RouterException("Controller Method {$controllerClass}::{$this->getActionParamValue()} should be callable");
                        } else {
                            View::make('templates/system/error.php',array());
                        }

                    } else {

                        // if request is post, make sure to verify nonce
                        if( SCMUtility::requestIsPost() )
                        {

                            if( ! in_array($this->getActionParamValue(),$this->csrfExemptedActions) )
                            {

                                if( ! $this->isNonceIsValid($this->getPostNonceValue()) )
                                {

                                    if(Settings::isDebugMode())
                                    {
                                        throw new RouterException("Invalid Token!");
                                    } else {
                                        View::make('templates/system/error.php',array());
                                    }

                                } else {

                                    $controllerInstance->{$this->getActionParamValue()}();

                                }

                            } else {

                                $controllerInstance->{$this->getActionParamValue()}();

                            }



                        } else {

                            $controllerInstance->{$this->getActionParamValue()}();

                        }



                    }

                } else {

                    if(Settings::isDebugMode())
                    {
                        throw new RouterException("Controller {$controllerClass} not found");
                    } else {
                        View::make('templates/system/error.php',array());
                    }

                }


            } else {

                $controller = new MainPageController();
                $controller->index();

            }

        } else {
            // do nothing, its not our business
        }

    }

    /**
     * check if the base route is ACM Route, this is the foundation of routing
     * this will be the basis whether we will do stuffs in here or just totally ignore all
     *
     * @return bool
     */
    protected function isBaseRouteIsACM()
    {
        if($this->baseRoute == self::ACMBaseRoute) return true;

        return false;
    }

    /**
     * check if the state parameter is present in query string
     * this will handle what controller to be instantiated
     *
     * @return bool
     */
    protected function isStatePresentInQueryString()
    {
        if( isset($_GET['state']) ) return true;

        return false;
    }

    /**
     * get the value of state param in $_GET query string
     *
     * @return mixed
     */
    public function getStateParamValue()
    {
        return ucfirst(SCMUtility::cleanText($_GET['state']));
    }

    /**
     * check if the action param is present in query string, this will
     * handle what method should be executed in current controller which is on (state)
     *
     * @return bool
     */
    protected function isActionPresentInQueryString()
    {
        if( isset($_GET['action']) ) return true;

        return false;
    }

    /**
     * get the value of action param in $_GET query string
     *
     * @return mixed
     */
    public function getActionParamValue()
    {
        return SCMUtility::cleanText($_GET['action']);
    }

    /**
     * check if the postAction param is present in a $_POST action
     *
     * @return bool
     */
    protected function isThereIsPostAction()
    {
        if( isset($_POST['postAction']) ) return true;

        return false;
    }

    /**
     * check if nonce is present
     *
     * @return bool
     */
    protected function isNonceIsPresent()
    {
        if( isset($_POST['_nonce']) ) return true;

        return false;
    }

    /**
     * get the post action value in $_POST
     *
     * @return mixed
     */
    protected function getPostActionValue()
    {
        return SCMUtility::cleanText($_POST['postAction']);
    }

    /**
     * get the nonce in $_POST action, this will be use for CSRF protection
     *
     * @return mixed
     */
    protected function getPostNonceValue()
    {
        return SCMUtility::cleanText( isset($_POST['_nonce']) ? $_POST['_nonce'] : '' );
    }

    /**
     * build the controller class name | ready for instantiation
     *
     * @param $state
     * @return string
     */
    protected function buildControllerClassName($state)
    {

        $controllerClass = "SCM\\Controller\\{$state}Controller";

        return $controllerClass;
    }

    /**
     * check if nonce is valid
     *
     * @param $postNonce
     * @return bool
     */
    protected function isNonceIsValid($postNonce)
    {
        if( ! wp_verify_nonce($postNonce,'scm_nonce') ) return false;

        return true;
    }

}