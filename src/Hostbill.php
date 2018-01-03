<?php

namespace AbuseIO\FindContact;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use Illuminate\Support\Facades\Log;


/**
 * TEMPLATE FILE!
 * You will need to fill in all these functions and activate this module in the configuration.
 * In the Examples dir you can find a few examples.
 *
 * The return should be either FALSE (not found) or a valid Contact object.
 */
class Hostbill
{
    const DEBUG = true;


    private $hbw;


    /**
     * Create a new Findcontact instance
     */
    public function __construct()
    {

        HBWrapper::setAPI(
            config("Findcontact.findcontact-hostbill.url"),
            config("Findcontact.findcontact-hostbill.api_id"),
            config("Findcontact.findcontact-hostbill.api_key")
        );
        $this->hbw = HBWrapper::singleton();
    }

    /**
     * Get the email address registered for this ip.
     * @param  string $ip IPv4 Address
     * @return object       Returns contact object or false.
     *
     * this gets return from getServiceByIP
     *
     *{
     *      "services": [
     *         {
     *            "id": "188",
     *            "client_id": "23",
     *            "order_id": "255",
     *            "product_id": "17",
     *            "parent_id": "0",
     *            "date_created": "2017-02-16",
     *            "domain": "",
     *            "server_id": "2",
     *            "payment_module": "7",
     *            "firstpayment": "110.00",
     *            "total": "110.00",
     *            "billingcycle": "Monthly",
     *            "next_due": "2017-06-30",
     *            "due_day": "31",
     *            "next_invoice": "2017-06-23",
     *            "status": "Active",
     *            "label": "",
     *            "username": "testzxmx",
     *            "autosuspend": "0",
     *            "autosuspend_date": "0000-00-00",
     *            "date_changed": "2017-06-01 07:18:03",
     *            "synch_date": "0000-00-00 00:00:00",
     *            "synch_error": "0",
     *            "user_error": "0",
     *            "domain_error": "0",
     *            "notes": "",
     *            "manual": "0",
     *            "extra_details": "a:0:{}",
     *            "firstname": "Christopher",
     *            "lastname": "Nolun",
     *            "ip": "192.168.0.36",
     *            "additional_ip": [
     *               "192.168.0.32",
     *               "192.168.0.33"
     *            ]
     *         }
     *      ],
     *      "success": true,
     *      "call": "searchServiceByIP",
     *      "server_time": 1497597094
     *   }
     *
     */
    public function getContactByIp($ip)
    {
        $result = false;
        try {
            $data = $this->hbw->searchServiceByIP(['ip' => $ip]);
//            dd($data['services']);

            if (!empty($data) && array_key_exists('services', $data) && is_array($data['services'])) {
                if ($service = array_shift($data['services'])) {
                    $name = implode(
                        [
                            $service['firstname'],
                            $service['lastname']
                        ],
                        ' '
                    );

                    // construct new contact
                    $result = new Contact();
                    $result->name = $name;
                    $result->reference = $name;
                    $result->email = $this->getEmailAddress($service['client_id']);
                    $result->enabled = true;
                    $result->auto_notify = true;
                    $result->account_id = Account::getSystemAccount()->id;
                    $result->api_host = '';
                }
            }
        } catch (\Exception $e) {
            $result = false;
            Log::debug("Error while talking to the HOSTBILL API : " . $e->getMessage());
        }

        return $result;
    }


    /**
     * Get the email address registered for this domain.
     * @param  string $ip Domain name
     * @return object       Returns contact object or false.
     */
    public function getContactByDomain($domain)
    {
        $ip = gethostbyname($domain);

        if ($ip !== $domain) {
            return $this->getContactByIp($ip);
        }
        return false;
    }

    /**
     * Get the email address registered for this ip.
     * @param  string $id ID/Contact reference
     * @return object       Returns contact object or false.
     */
    public function getContactById($id)
    {
        return false;
    }

    private function getEmailAddress($id)
    {
        $data = $this->hbw->getClientDetails(['id' => $id]);
        if ($data && array_key_exists('client', $data) && filter_var($data['client']['email'], FILTER_VALIDATE_EMAIL)) {
            return $data['client']['email'];
        }

        throw new Exception('Method getClientDetails does not return expected client');

    }
}
