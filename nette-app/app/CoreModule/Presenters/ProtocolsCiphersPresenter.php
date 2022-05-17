<?php

/**
 * Presenter ProtocolsCiphersPresenter modulu CoreModule
 * 
 * @category    CoreModule
 * @author      Václav Šnajdr
 * @version     1.0
 * 
 * Webová aplikace pro testování zranitelností webového serveru
 * Diplomová práce
 * Vysoké učení technické v Brně
 * Fakulta elektrotechniky a komunikačních technologií
 * Ústav telekomunikací
 * Rok 2021
 */

declare(strict_types=1);

namespace App\CoreModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use App\CoreModule\Models\ProtocolsCiphersManager;
use App\CoreModule\InputValidator;

class ProtocolsCiphersPresenter extends Presenter {

	private $protocolsCiphersManager;
	// Slovník protokolů
	private $dic_prot = ["ssl2" => "SSLv2",
						"ssl3" => "SSLv3",
						"tls1" => "TLSv1.0", 
						"tls1_1" => "TLSv1.1", 
						"tls1_2" => "TLSv1.2", 
						"tls1_3" => "TLSv1.3"];
	private $success = false;	// výchozí hodnota

    // Konstruktor
    public function __construct(ProtocolsCiphersManager $protocolsCiphersManager)
    {
        $this->protocolsCiphersManager = $protocolsCiphersManager;
    }

    public function renderDefault(): void {
		$this->template->timeNow = $this->protocolsCiphersManager->timeNow;
		$this->template->url = $this->protocolsCiphersManager->url;
		$this->template->dns = $this->protocolsCiphersManager->dns;
		$this->template->ips = $this->protocolsCiphersManager->ips;
		$this->template->ports_only = $this->protocolsCiphersManager->ports_only;
		$this->template->port_labels = $this->protocolsCiphersManager->port_labels;
		$this->template->infos = $this->protocolsCiphersManager->infos;		
		$this->template->https = $this->protocolsCiphersManager->https;
		$this->template->online = $this->protocolsCiphersManager->isOnline;
		$this->template->isHsts = $this->protocolsCiphersManager->isHsts;
		$this->template->subject = $this->protocolsCiphersManager->subject;
		$this->template->issuer = $this->protocolsCiphersManager->issuer;
		$this->template->renegotiation = $this->protocolsCiphersManager->renegotiation;
		$this->template->crime = $this->protocolsCiphersManager->crime;
		$this->template->compression = $this->protocolsCiphersManager->compression;
		$this->template->compression_label = $this->protocolsCiphersManager->compression_label;
		$this->template->keyBits_label = $this->protocolsCiphersManager->keyBits_label;
		$this->template->keyBits = $this->protocolsCiphersManager->keyBits;
		$this->template->keyType = $this->protocolsCiphersManager->keyType;
		$this->template->signature = $this->protocolsCiphersManager->signature;
		$this->template->signature_label = $this->protocolsCiphersManager->signature_label;
		$this->template->validFrom = $this->protocolsCiphersManager->validFrom;
		$this->template->validTo = $this->protocolsCiphersManager->validTo;
		$this->template->isValid = $this->protocolsCiphersManager->isValid;
		$this->template->prot_labels = $this->protocolsCiphersManager->prot_labels;
		$this->template->protocols = $this->protocolsCiphersManager->protocols;
		$this->template->prot_count = $this->protocolsCiphersManager->prot_count;
		$this->template->arrayP = $this->protocolsCiphersManager->arrayP;
		$this->template->cipher_labels = $this->protocolsCiphersManager->cipher_labels;
		$this->template->openssl_version = $this->protocolsCiphersManager->openssl_version; 
		$this->template->dic_prot = $this->dic_prot;
		$this->template->success = $this->success;
		$this->template->score = $this->protocolsCiphersManager->score;
		$this->template->vulnerabilities = $this->protocolsCiphersManager->vulnerabilities;
		$this->template->vulnMapping = $this->protocolsCiphersManager->vulnMapping;
    }
    
    // Vytvoření formuláře pro zadání serveru
    protected function createComponentServerForm(): Form {
		$form = new Form;      
		// Renderer upravuje vzhled formuláře podle Bootstrap 4
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = 'div class="text-center"';
	    $renderer->wrappers['pair']['container'] = 'div class="form-group"';
	    //$renderer->wrappers['pair']['.error'] = 'has-danger';
	    //$renderer->wrappers['control']['container'] = 'div class="text-center"';
	    //$renderer->wrappers['control']['description'] = 'span class="form-text"';
	    //$renderer->wrappers['control']['errorcontainer'] = 'span class="form-control-feedback"';
        //$renderer->wrappers['control']['.error'] = 'is-invalid';
        
        $form->addText('server', '')
			->setRequired()->setHTMLAttribute('class', 'w-75 text-center mx-auto form-control rounded-bottom-0')
			->setHTMLAttribute('placeholder', 'example.com')
			->addRule(
				'InputValidator::isCorrect',
				'Zadaný server musí být ve formátu: example.com');
		$form->addSubmit('send', '>>   Otestovat vzdálený server   <<')->setHTMLAttribute('class', 'w-75 btn btn-outline-secondary rounded-top-0');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;
    }

    public function formSucceeded(Form $form, $data): void {
		// tady zpracujeme data odeslaná formulářem
		// $data->server obsahuje název serveru
		$this->protocolsCiphersManager->runTest($data->server);
		$this->success = true;
	}
}