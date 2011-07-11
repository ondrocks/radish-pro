<?php
class ImapController
{
        private $ImapClient;
        function __construct()
        {
		if(isset($_SESSION['EMAIL_ADDRESS']) && isset($_SESSION['EMAIL_PASS']))
        	        $this->ImapClient = new ImapClient('gmail', $_SESSION['EMAIL_ADDRESS'], $_SESSION['EMAIL_PASS']);
        }
        public function handlePost()
        {
                if($_SERVER['REQUEST_METHOD'] == 'POST')
                {

                }
                return false;
        }
        public function listEmails()
        {
			echo json_encode($this->ImapClient->getEmails());
            $this->ImapClient->closeMBox();
        }

		public function getMsg($id)
		{
			echo json_encode($this->ImapClient->getEmail($id));
			$this->ImapClient->closeMBox();
		}
}

?>
