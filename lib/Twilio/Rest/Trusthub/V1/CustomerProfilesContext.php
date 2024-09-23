<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Trusthub
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Trusthub\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;
use Twilio\InstanceContext;
use Twilio\Rest\Trusthub\V1\CustomerProfiles\CustomerProfilesChannelEndpointAssignmentList;
use Twilio\Rest\Trusthub\V1\CustomerProfiles\CustomerProfilesEntityAssignmentsList;
use Twilio\Rest\Trusthub\V1\CustomerProfiles\CustomerProfilesEvaluationsList;


/**
 * @property CustomerProfilesChannelEndpointAssignmentList $customerProfilesChannelEndpointAssignment
 * @property CustomerProfilesEntityAssignmentsList $customerProfilesEntityAssignments
 * @property CustomerProfilesEvaluationsList $customerProfilesEvaluations
 * @method \Twilio\Rest\Trusthub\V1\CustomerProfiles\CustomerProfilesChannelEndpointAssignmentContext customerProfilesChannelEndpointAssignment(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\CustomerProfiles\CustomerProfilesEntityAssignmentsContext customerProfilesEntityAssignments(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\CustomerProfiles\CustomerProfilesEvaluationsContext customerProfilesEvaluations(string $sid)
 */
class CustomerProfilesContext extends InstanceContext
    {
    protected $_customerProfilesChannelEndpointAssignment;
    protected $_customerProfilesEntityAssignments;
    protected $_customerProfilesEvaluations;

    /**
     * Initialize the CustomerProfilesContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid The unique string that we created to identify the Customer-Profile resource.
     */
    public function __construct(
        Version $version,
        $sid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'sid' =>
            $sid,
        ];

        $this->uri = '/CustomerProfiles/' . \rawurlencode($sid)
        .'';
    }

    /**
     * Delete the CustomerProfilesInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        return $this->version->delete('DELETE', $this->uri, [], [], $headers);
    }


    /**
     * Fetch the CustomerProfilesInstance
     *
     * @return CustomerProfilesInstance Fetched CustomerProfilesInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): CustomerProfilesInstance
    {

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $payload = $this->version->fetch('GET', $this->uri, [], [], $headers);

        return new CustomerProfilesInstance(
            $this->version,
            $payload,
            $this->solution['sid']
        );
    }


    /**
     * Update the CustomerProfilesInstance
     *
     * @param array|Options $options Optional Arguments
     * @return CustomerProfilesInstance Updated CustomerProfilesInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []): CustomerProfilesInstance
    {

        $options = new Values($options);

        $data = Values::of([
            'Status' =>
                $options['status'],
            'StatusCallback' =>
                $options['statusCallback'],
            'FriendlyName' =>
                $options['friendlyName'],
            'Email' =>
                $options['email'],
        ]);

        $headers = Values::of(['Content-Type' => 'application/x-www-form-urlencoded' ]);
        $payload = $this->version->update('POST', $this->uri, [], $data, $headers);

        return new CustomerProfilesInstance(
            $this->version,
            $payload,
            $this->solution['sid']
        );
    }


    /**
     * Access the customerProfilesChannelEndpointAssignment
     */
    protected function getCustomerProfilesChannelEndpointAssignment(): CustomerProfilesChannelEndpointAssignmentList
    {
        if (!$this->_customerProfilesChannelEndpointAssignment) {
            $this->_customerProfilesChannelEndpointAssignment = new CustomerProfilesChannelEndpointAssignmentList(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->_customerProfilesChannelEndpointAssignment;
    }

    /**
     * Access the customerProfilesEntityAssignments
     */
    protected function getCustomerProfilesEntityAssignments(): CustomerProfilesEntityAssignmentsList
    {
        if (!$this->_customerProfilesEntityAssignments) {
            $this->_customerProfilesEntityAssignments = new CustomerProfilesEntityAssignmentsList(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->_customerProfilesEntityAssignments;
    }

    /**
     * Access the customerProfilesEvaluations
     */
    protected function getCustomerProfilesEvaluations(): CustomerProfilesEvaluationsList
    {
        if (!$this->_customerProfilesEvaluations) {
            $this->_customerProfilesEvaluations = new CustomerProfilesEvaluationsList(
                $this->version,
                $this->solution['sid']
            );
        }

        return $this->_customerProfilesEvaluations;
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get(string $name): ListResource
    {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments): InstanceContext
    {
        $property = $this->$name;
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Trusthub.V1.CustomerProfilesContext ' . \implode(' ', $context) . ']';
    }
}
