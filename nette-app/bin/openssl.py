#!/usr/bin/env python3

# Skript je součástí webové aplikace Appserver, ale je možné ho využít i jinde.
# Autor:            Václav Šnajdr
# Verze:            1.0
# Rok:              2021
# Diplomová práce:  Webová aplikace pro testování zranitelností webového serveru
# Vysoké učení technické v Brně
# Fakulta elektrotechniky a komunikačních technologií

import os, argparse, io

def test_protocols(serverport):
    protocols =  ['ssl2', 'ssl3', 'tls1', 'tls1_1', 'tls1_2']
    # Uložení do ciphers všechny kryptografické sady nástroje openssl
    ciphers = os.popen("openssl ciphers ALL:eNULL | \
        tr : ' '").read().split(' ')
    out = ''
    # Cyklus pro spouštění příkazu s konkrétními protokoly a kryptografickými sadami
    for p in protocols:        
        for c in ciphers:
            out += os.popen('openssl s_client -connect '+serverport+' -cipher '+c+' -'+p+' < /dev/null > /dev/null 2>&1 && echo *'+p+':'+c+',').read()
    bf1 = io.StringIO(out)
    line1 = bf1.readline()
    
    tls1_3 = "TLS_AES_256_GCM_SHA384 TLS_CHACHA20_POLY1305_SHA256 TLS_AES_128_GCM_SHA256"
    ciphersTls13 = tls1_3.split(' ')

    out = ''
    # Cyklus pro spouštění příkazu pro TLS 1.3 a kryptografickými sadami       
    for c in ciphersTls13:
        out += os.popen('openssl s_client -connect '+serverport+' -ciphersuites '+c+' -tls1_3 < /dev/null > /dev/null 2>&1 && echo *tls1_3:'+c+',').read()
    bf2 = io.StringIO(out)
    line2 = bf2.readline()

    suits = ''
    
    # Označený řádek hvězdičkou (*) je uložen do suits, který se po cyklu vypíše
    while line1:
        if ord('*') == ord(line1[0]):
            line1 = line1[:0] + '' + line1[0 + 1:]
            suits += line1
            line1 = bf1.readline()
        else:
            line1 = bf1.readline()

    while line2:
        if ord('*') == ord(line2[0]):
            line2 = line2[:0] + '' + line2[0 + 1:]
            suits += line2
            line2 = bf2.readline()
        else:
            line2 = bf2.readline()

    print(suits)        

def main():
    parser = argparse.ArgumentParser("Need server with port")
    parser.add_argument("serverport", type=str)
    args = parser.parse_args()

    test_protocols(args.serverport)

if __name__ == '__main__':
    main()