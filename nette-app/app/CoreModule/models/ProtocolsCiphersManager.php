<?php

/**
 * Model ProtocolsCiphersManager modulu CoreModule
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

namespace App\CoreModule\Models;

use Nette;
use Nette\Utils\Arrays;
use Nette\Utils\Json;


/**
 * @property string $timeNow
 * @property string $url
 * @property string $ips
 * @property string $dns
 * @property array $ports_only
 * @property array $port_labels
 * @property array $infos
 * @property bool $https
 * @property bool $isOnline
 * @property bool $isHsts
 * @property array $subject
 * @property array $issuer
 * @property bool $renegotiation
 * @property bool $crime
 * @property string $compression
 * @property array $compression_label
 * @property string $keyType
 * @property string $keyBits
 * @property array $keyBits_label
 * @property string $signature
 * @property array $signature_label
 * @property string $validFrom
 * @property string $validTo
 * @property bool $isValid
 * @property array $protocols
 * @property array $prot_count
 * @property array $prot_labels
 * @property array $arrayP
 * @property array $cipher_labels
 * @property string $openssl_version
 * @property int $score
 * @property array $vulnerabilities
 * @property array $vulnMapping
 */

class ProtocolsCiphersManager {
    use Nette\SmartObject;

    private string $timeNow = "";   // Datum a čas testování
    private string $dns = "";       // DNS název s IP adresou serveru
    private array $ips = array();   // Pole alternativních IP adres serveru
    private array $ports_only = array();    // Pole oskenovaných portů
    private array $port_labels = array();   // Pole portů s označením 
    private array $infos = array();         // Pole informací o serveru
    private bool $https = FALSE;    // Zda server podporuje HTTPS
    private string $url = "";       // Adresa testovaného serveru
    private bool $isOnline = FALSE; // Zda je server dostupný
    private bool $isHsts = FALSE;   // Zda server podporuje HSTS
    private $subject = array();     // Informace o sunjektu
    private $issuer = array();      // Informace o vydavateli
    private bool $renegotiation = FALSE;    // Zda je povolena Renegotiation
    private bool $crime = FALSE;    // Zranitelnost CRIME
    private string $compression = "";   // Typ použité komprese
    private $compression_label = array();   // Označení komprese
    private string $keyType = "";   // Typ veřejného klíče certifikátu
    private string $keyBits = "";   // Délka veřejného klíče certifikátu
    private $keyBits_label = array();   // Označení délky veřejného klíče certifikátu
    private string $signature = ""; // Podpis certifikátu
    private $signature_label = array(); // Označení podpisu certifikátu
    private string $validFrom = ""; // Platnost certifikátu Od
    private string $validTo = "";   // Platnost certifikátu Do
    private bool $isValid = FALSE;  // Platnost certifikátu
    private $protocols = array();   // Pole používaných protokolů
    private $prot_count = array();  // Pomocné pole s protokoly s počtem, kolikrat se daný protokol objevil
    private $arrayP = array();      // Pole protokolů se ciphersuites
    private $prot_labels = array(); // Pole protokolů s označením
    private $cipherMapping = array();   // Pole názvů ciphersuits exportovaných z JSON
    private $cipher_labels = array();   // Pole označených ciphersuits
    private string $openssl_version = "";   // Verze openSSL
    private int $score = 0;         // Výsledek skóre testovaného serveru
    private $vulnerabilities = array(); // Pole zranitelností
    private $vulnMapping = array();   // Pole zranitelností exportovaných z JSON

    public function getTimeNow(): string {
        return $this->timeNow;
    }

    public function setUrl($url): void {
        $this->url = $url;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getDns(): string {
        return $this->dns;
    }

    public function getIps(): array {
        return $this->ips;
    }

    public function getPort_labels(): array {
        return $this->port_labels;
    }

    public function getPorts_only(): array {
        return $this->ports_only;
    }

    public function getInfos(): array {
        return $this->infos;
    }

    public function getHttps(): bool {
        return $this->https;
    }

    public function getIsHsts(): bool {
        return $this->isHsts;
    }

    public function getIsOnline(): bool {
        return $this->isOnline;
    }

    public function getSubject(): array {
        return $this->subject;
    }

    public function getIssuer(): array {
        return $this->issuer;
    }

    public function getRenegotiation(): bool {
        return $this->renegotiation;
    }

    public function getCrime(): bool {
        return $this->crime;
    }

    public function getCompression(): string {
        return $this->compression;
    }

    public function getCompression_label(): array {
        return $this->compression_label;
    }

    public function getKeyType(): string {
        return $this->keyType;
    }

    public function getKeyBits(): string {
        return $this->keyBits;
    }

    public function getKeyBits_label(): array {
        return $this->keyBits_label;
    }

    public function getSignature(): string {
        return $this->signature;
    }

    public function getSignature_label(): array {
        return $this->signature_label;
    }

    public function getValidFrom(): string {
        return $this->validFrom;
    }

    public function getValidTo(): string {
        return $this->validTo;
    }

    public function getIsValid(): bool {
        return $this->isValid;
    }

    public function getProtocols(): array {
        return $this->protocols;
    }

    public function getProt_count(): array {
        return $this->prot_count;
    }

    public function getArrayP(): array {
        return $this->arrayP;
    }

    public function getProt_labels(): array {
        return $this->prot_labels;
    }

    public function getCipher_labels(): array {
        return $this->cipher_labels;
    }

    public function getOpenssl_version(): string {
        return $this->openssl_version;
    }
    
    public function getScore(): int {
        return $this->score;
    }

    public function getVulnerabilities(): array {
        return $this->vulnerabilities;
    }

    public function getVulnMapping(): array {
        return $this->vulnMapping;
    }

    // Hlavní spustitelná metoda
    public function runTest($url): void {
        // Naplnění zranitelností z JSON do paměti
        $json = file_get_contents("../bin/vuln_info.json");
        $this->vulnMapping = Json::decode($json, Json::FORCE_ARRAY);
        // Datum a čas testování
        $this->timeNow = date("Y-m-d H:i:s");        
        // Start testování
        $this->setUrl($url);
        if ($this->runPing()) {
            if ($this->runInfoScan()) {
                $this->runCertInfo();
                $this->runHstsTest();
                $this->runCiphers();
            }            
        }
    }

    private function runPing(): bool {
        $command = escapeshellcmd('../bin/pingtest.py '.$this->url);
        $out = shell_exec($command);
        $out_new = str_replace("\n", '', $out);
        if ($out_new == "ONLINE") {   
            $this->isOnline = TRUE;         
            return $this->isOnline;
        }           
        else return $this->isOnline = FALSE;
    }

    private function runInfoScan(): bool {
        $command = escapeshellcmd('../bin/nmapinfo.py '.$this->url);
        $out = shell_exec($command);
        if($this->get_info_about_server($out))
            return true;
        else return false;
    }

    private function runCertInfo(): void {
        $command = escapeshellcmd('../bin/certinfo.py '.$this->url);
        $out = shell_exec($command);
        $this->get_cert_info($out);
    }

    private function runHstsTest(): void {
        $this->isHsts = FALSE;
        $command = escapeshellcmd('../bin/hststest.py '.$this->url);
        $out = shell_exec($command);
        $out_new = str_replace("\n", '', $out);
        if ($out_new == "HSTS") {
            $this->isHsts = TRUE;
            $this->score += 15;
        }
        else {
            $this->isHsts = FALSE;           
            $this->set_vulnerability("HSTS");
        }
    }

    private function runCiphers(): void {
        $command = escapeshellcmd('../bin/openssl.py '.$this->url.':443');
        $command_openssl = 'openssl version';
        $out = shell_exec($command);
        $out_new = str_replace("\n", '', $out);
        $this->openssl_version = shell_exec($command_openssl);
        $test = "ssl3:RC4-64-MD5,tls1:PSK-NULL-SHA,tls1:DHE-DSS-RC4-SHA,tls1:EXP1024-DES-CBC-SHA,tls1_1:ECDHE-RSA-AES256-SHA,tls1_1:RC4-MD5,tls1_2:ECDHE-RSA-AES256-GCM-SHA384,tls1_3:TLS_AES_128_GCM_SHA256,tls1_3:TLS_AES_128_CCM_SHA256";
        $this->fill_protocols_and_ciphers_to_arrays($out_new);
    }

    private function get_info_about_server($out): bool {
        $lines = explode("\n", $out);
        // Odstraní prázdný poslední prvek v poli
        array_pop($lines);
        
        // Uloží DNS název s IP adresou
        $this->dns = $lines[0];
        $ips = $lines[1];
        if (empty($ips)) $ips = "Žádné alternativní adresy";
        $this->ips = explode(";", $ips);
        
        // Uloží porty a označí je
        $ports = $lines[2];
        $ports = explode(";", $ports);
        array_pop($ports);
        array_pop($ports);
        $this->ports_only = $ports;
        foreach ($ports as $p) {
            if (strpos($p, "80") !== false 
            && strpos($p, "open") !== false) {
                $this->score += -3;
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("HTTP");
            } elseif (strpos($p, "21") !== false && strpos($p, "open") !== false) {
                $this->score += -5;
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("21");
            } elseif (strpos($p, "22") !== false && strpos($p, "open") !== false) {
                $this->score += -10;
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("22");
            } elseif (strpos($p, "23") !== false && strpos($p, "open") !== false) {
                $this->score += -10;
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("23");
            } elseif (strpos($p, "53") !== false && strpos($p, "open") !== false) {
                $this->port_labels[$p] = "warning";
                $this->set_vulnerability("23");
            } elseif (strpos($p, "113") !== false && strpos($p, "open") !== false) {
                $this->score += -5;
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("113");
            } elseif (strpos($p, "3389") !== false && strpos($p, "open") !== false) {
                $this->score += -10;
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("3389");
            } elseif (strpos($p, "443") !== false && strpos($p, "open") !== false) {
                $this->score += 50;
                $this->port_labels[$p] = "success";
                $this->https = true;
            } elseif (strpos($p, "443") !== false && strpos($p, "open") !== true) {
                $this->port_labels[$p] = "danger";
                $this->set_vulnerability("HTTPS");
            } else {
                $this->port_labels[$p] = "success";
            }
        }
        
        // Uloží informace o poskytovateli
        $infos = $lines[3];
        $this->infos = explode(";", $infos);
        // Odstraní se poslední nepotřebné řádky
        $this->infos = array_filter($this->infos);
        array_pop($this->infos);

        return $this->https;
    }

    private function get_cert_info(string $out): void {
        $lines = explode("\n", $out);
        // Odstraní prázdné hodnoty z pole
        $lines = array_filter($lines);    
        // Vyplní informace o certifikátu do proměnných
        foreach ($lines as $line) {
            if (strpos($line, "subject") !== false) {
                $tmp = preg_replace('/subject=/', '$2', $line);
                $subjectInfo = preg_split('/, (?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/', $tmp);
                foreach ($subjectInfo as $i) {
                    array_push($this->subject, $i);
                }
            } elseif (strpos($line, "issuer") !== false) {
                $tmp = preg_replace('/issuer=/', '$2', $line);
                $issuerInfo = preg_split('/, (?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/', $tmp);
                foreach ($issuerInfo as $i) {
                    array_push($this->issuer, $i);
                }          
            } elseif (strpos($line, "Renegotiation") !== false) {
                if (preg_match('/IS NOT/', $line)) {
                    $this->renegotiation = FALSE;
                    $this->set_vulnerability("Renegotiation");
                } else {
                    $this->renegotiation = TRUE;
                    $this->score += 5;
                }
            } elseif (strpos($line, "Compression") !== false) {
                $this->compression = preg_replace('/Compression: /', '$2', $line);
                if (preg_match('/NONE/', $line)) {
                    $this->crime = FALSE;
                    $this->compression = $this->compression . " (žádná)";
                    $this->compression_label[$this->compression] = "success";
                } else {                   
                    $this->crime = TRUE;
                    $this->compression_label[$this->compression] = "danger";
                    $this->set_vulnerability("CRIME");
                    $this->score -= 5;
                }
            } elseif (strpos($line, "Public Key type") !== false) {
                $tmp = preg_replace('/Public Key type: /', '$2', $line);
                $this->keyType = strtoupper($tmp);
            } elseif (strpos($line, "Public Key bits") !== false) {
                $tmp = preg_replace('/Public Key bits: /', '$2', $line);
                if ($this->keyType == "RSA") {
                    $this->keyBits = $tmp;
                    if ($tmp >= 2048) {                    
                        $this->keyBits_label[$tmp] = "success";
                    } else {
                        $this->keyBits_label[$tmp] = "danger";
                        $this->score += -10;
                        $this->set_vulnerability("Slabý klíč");
                    }
                } elseif ($this->keyType == "EC") {
                    $this->keyBits = $tmp;
                    if ($tmp >= 256) {                    
                        $this->keyBits_label[$tmp] = "success";
                    } else {
                        $this->keyBits_label[$tmp] = "danger";
                        $this->score += -10;
                        $this->set_vulnerability("Slabý klíč");
                    }
                }
            } elseif (strpos($line, "Signature Algorithm") !== false) {
                $tmp = preg_replace('/Signature Algorithm: /', '$2', $line);
                if (preg_match('/\Asha1/', $tmp)) {
                    $this->signature_label[$tmp] = "danger";
                    $this->score += -5;
                    $this->set_vulnerability("SHA1");
                }
                else $this->signature_label[$tmp] = "success";
                $this->signature = $tmp;
            } elseif (strpos($line, "Not valid before") !== false) {
                $tmp = preg_replace('/Not valid before: /', '$2', $line);
                $this->validFrom = preg_replace('/T/', '$2 ', $tmp);
            } elseif (strpos($line, "Not valid after") !== false) {
                $tmp = preg_replace('/Not valid after: /', '$2', $line);
                $this->validTo = preg_replace('/T/', '$2 ', $tmp);
            }
        }
        // Kontrola platnosti certifikátu
        if (strtotime($this->validFrom) < strtotime($this->timeNow) && strtotime($this->timeNow) < strtotime($this->validTo)) {
            $this->isValid = TRUE;
        } else {
            $this->isValid = FALSE;
            $this->score -= 10;
            $this->set_vulnerability("Neplatný certifikát");
        }      
    }

    private function fill_protocols_and_ciphers_to_arrays(string $out_new): void {
        $json = file_get_contents("../bin/cipher_mapping.json");
        $this->cipherMapping = Json::decode($json, Json::FORCE_ARRAY);
        $lines = explode(",", $out_new);
        array_pop($lines);
        $i = 0;
        // Vyplní pole používaných protokolů a pole protokolů se ciphersuits
        foreach ($lines as $line) {
            $temp = array();
            $p = explode(":", $line);
            // Přepíše název openssl ciphersuits na IANA název,
            // pokud existuje
            if (array_search($p[1], $this->cipherMapping)){
                $p[1] = array_search($p[1], $this->cipherMapping);
            } else {
                continue;
            }        
            $temp[$p[0]] = $p[1];
            if (in_array($p[0], $this->protocols)){
                array_push($this->arrayP, $temp);        
            } else {    
                array_push($this->protocols, $p[0]);
                array_push($this->arrayP, $temp);
                $i++;
            }           

            // Označí ciphersuits podle podmínek
            $this->cipher_labels[$p[1]] = "success";
            if (preg_match('/CBC/', $p[1])) {
                $this->cipher_labels[$p[1]] = "warning";
                $this->set_vulnerability("LUCKY13");
            }
            if (preg_match('/SHA\z/', $p[1])) {
                $this->cipher_labels[$p[1]] = "warning";
                $this->set_vulnerability("SHA1");
            }
            if (preg_match('/\ATLS_RSA/', $p[1])) {
                $this->cipher_labels[$p[1]] = "warning";
                $this->set_vulnerability("Výměna klíče přes RSA");
            }
            if (preg_match('/\ATLS_DH/', $p[1])) {
                $this->cipher_labels[$p[1]] = "warning";
                $this->set_vulnerability("RACCOON Útok");
            }
            if (preg_match('/\ATLS_DHE/', $p[1])) {
                $this->cipher_labels[$p[1]] = "warning";
                $this->set_vulnerability("RACCOON Útok");
            }
            if (preg_match('/EXPORT/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("Logjam Útok");
            }
            if (preg_match('/RC2/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("RC");
            }
            if (preg_match('/RC4/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("RC");
            }
            if (preg_match('/MD5/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("MD5");
            }
            if (preg_match('/NULL/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("NULL");
            }
            if (preg_match('/DES/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("DES");
            }
            if (preg_match('/DSS/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("DSS");
            }
            if (preg_match('/3DES/', $p[1])) {
                $this->cipher_labels[$p[1]] = "danger";
                $this->set_vulnerability("DES");
            }          
        }
        $this->protocols = array_reverse($this->protocols, true);

        // Spočítá se, kolik ciphersuits má konkrétní protokol a označí používané protokoly
        $c = 0;
        foreach ($this->protocols as $prot) {
            foreach ($this->arrayP as $array) {
                if (array_key_exists($prot, $array)) {
                    $c++;
                }
            }  
            $this->prot_count[$prot] = $c;
            $c = 0;

            if ($prot == "ssl2") {
                $this->score += -15;
                $this->prot_labels[$prot] = "danger";
                $this->set_vulnerability("BEAST Útok");
                $this->set_vulnerability("DROWN Útok");
            } elseif ($prot == "ssl3") {
                $this->score += -15;
                $this->prot_labels[$prot] = "danger";
                $this->set_vulnerability("BEAST Útok");
                $this->set_vulnerability("POODLE Útok");
            } elseif ($prot == "tls1") {
                $this->score += -10;
                $this->prot_labels[$prot] = "danger";
                $this->set_vulnerability("BEAST Útok");
            } elseif ($prot == "tls1_1") {
                $this->score += -10;
                $this->prot_labels[$prot] = "danger";
            } elseif ($prot == "tls1_2") {
                $this->score += 10;
                $this->prot_labels[$prot] = "success";
            } elseif ($prot == "tls1_3") {
                $this->score += 20;
                $this->prot_labels[$prot] = "success";  
            } else {
                $this->prot_labels[$prot] = "success";
            }
        }
    }

    private function set_vulnerability(string $vuln): void {
        if (!in_array($vuln, $this->vulnerabilities)) {
            array_push($this->vulnerabilities, $vuln);
        }
    }
}