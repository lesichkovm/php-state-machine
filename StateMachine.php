<?php

class StateMachine {

    private $memory = [
        'state' => 'uninitialized',
        'history' => []
    ];
    private $config = [
        'states' => [],
        'transtions' => []
    ];
    private $matrix = [];

    function applyTransition($transitionName) {
        if ($this->canTransition($transitionName) == false) {
            return false;
        }
        $transition = $this->config['transitions'][$transitionName];
        
        $isValid = $this->validateTransition($transition);

        if ($isValid == false) {
            return false;
        }
        
        $to = $transition['to'];
        $this->setState($to);
        return true;
    }

    function canState($desiredState) {
        $state = $this->getState();
        if (in_array($state . '-' . $desiredState, $this->matrix)) {
            return true;
        }
        return false;
    }

    function validateTransition(array $transition) {
        $validators = $transition['validators'] ?? [];
        
        if (count($validators) < 1) {
            return true;
        }
        
        foreach ($validators as $validator) {
            $isOk = call_user_func($validator);
            if ($isOk == false) {
                return false;
            }
        }
        return true;
    }

    function canTransition($transitionName) {
        $state = $this->getState();
        $transition = $this->config['transitions'][$transitionName];
        $fromArray = is_array($transition['from']) ? $transition['from'] : [$transition['from']];
        $to = $transition['to'];
        
        $isValid = $this->validateTransition($transition);

        if ($isValid == false) {
            return false;
        }

        if (in_array($this->getState(), $fromArray)) {
            if (in_array($state . '-' . $to, $this->matrix)) {
                return true;
            }
        }
        return false;
    }

    function getConfig() {
        return $this->config;
    }

    function getHistory() {
        return $this->memory['history'];
    }

    function getPossibleTransitions() {
        $possibleTransitions = [];
        foreach ($this->config['transitions'] as $name => $transition) {
            $fromArray = is_array($transition['from']) ? $transition['from'] : [$transition['from']];
            if (in_array($this->getState(), $fromArray)) {
                $possibleTransitions[] = $name;
            }
        }
        return $possibleTransitions;
    }

    function getState() {
        return $this->memory['state'];
    }

    function fromString($string) {
        $this->memory = json_decode($string, true);
    }

    function setConfig($config) {
        $this->config = $config;
        $this->setState($this->config['states'][0]);
        /*
         * Generate Hash Matrix
         * from-to
         */
        foreach ($this->config['transitions'] as $transition) {
            $fromArray = is_array($transition['from']) ? $transition['from'] : [$transition['from']];
            $to = $transition['to'];
            foreach ($fromArray as $from) {
                $this->matrix[] = $from . '-' . $to;
            }
        }
    }

    function setState($state) {
        $this->memory['state'] = $state;
        $this->memory['history'][] = $state;
    }

    function toString() {
        return json_encode($this->memory);
    }

}
