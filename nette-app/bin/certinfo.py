#!/usr/bin/env python3

# Skript je součástí webové aplikace Appserver, ale je možné ho využít i jinde.
# Autor:            Václav Šnajdr
# Verze:            1.0
# Rok:              2021
# Diplomová práce:  Webová aplikace pro testování zranitelností webového serveru
# Vysoké učení technické v Brně
# Fakulta elektrotechniky a komunikačních technologií

import os, argparse, io

def cert_info(server):
    # Zjištění informací o certifikátu nástrojem Openssl
    out = ''
    out += os.popen('echo | openssl s_client -connect '+server+':443').read()
    bf = io.StringIO(out)
    line = bf.readline()

    info = ''
    while line:
        if "subject" in line:
            info += line
        elif "issuer" in line:
            info += line
        elif "Secure Renegotiation" in line:
            info += line
        elif "Compression" in line:
            info += line
        line = bf.readline()

    # Zjištění informací o certifikátu nástrojem Nmap
    out = ''
    out += os.popen('nmap -p 443 --script ssl-cert '+server).read()
    bf = io.StringIO(out)
    line = bf.readline()

    while line:
        if "Public Key type" in line:
            info += line.replace("| ","",1)
        elif "Public Key bits" in line:
            info += line.replace("| ","",1)
        elif "Signature Algorithm" in line:
            info += line.replace("| ","",1)
        elif "Not valid before" in line:
            info += line.replace("| ","",1)
        elif "Not valid after" in line:
            info += line.replace("| ","",1)
        line = bf.readline()

    print(info)

def main():
    parser = argparse.ArgumentParser("Need target")
    parser.add_argument("server", type=str)
    args = parser.parse_args()

    cert_info(args.server)

if __name__ == '__main__':
    main()