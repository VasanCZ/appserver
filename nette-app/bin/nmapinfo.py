#!/usr/bin/env python3

# Skript je součástí webové aplikace Appserver, ale je možné ho využít i jinde.
# Autor:            Václav Šnajdr
# Verze:            1.0
# Rok:              2021
# Diplomová práce:  Webová aplikace pro testování zranitelností webového serveru
# Vysoké učení technické v Brně
# Fakulta elektrotechniky a komunikačních technologií

import os, argparse, io

def nmap_info(server):
    ports = '21,22,23,53,80,113,443,3389'
    out = ''
    # Zjištění informací o serveru a sken vybraných portů
    out += os.popen('nmap -p'+ports+' '+server+' --script \
    whois-ip').read()
    bf = io.StringIO(out)
    line = bf.readline()

    info = ''
    alterIp = False
    # Dokud existuje řádek
    while line:    
        dns = "Nmap scan report for "
        ips = "Other addresses "
        porttext = "PORT"
        who = "Host script results:"
        # Podmínka pro přečtění IP a DNS názvu
        if dns in line:
            # Rozdělí se řetězec jednou a uloží se část pole[1]
            info += line.split(dns,1)[1]
        # Podmínka pro přečtení ostatních IP serveru
        elif ips in line:
            alterIp = True
            aips = line.split(": ",1)[1]
            info += aips.replace(' ', ';') 
        elif porttext in line:
            portlist = ''
            # Jestliže server neobsahuje alternativni IP adresy
            if not alterIp: info += '\n'
            line = bf.readline()
            while line:
                # Podmínka pro přečtení informací o poskytovateli serveru 
                if who in line:
                    line = bf.readline()
                    info += portlist.replace('\n', ';') 
                    whoinfo = ''
                    info += '\n'
                    while line:
                        line = line.replace("| ","",1) \
                        .replace("|_","",1)
                        whoinfo += line
                        line = bf.readline()
                    info += whoinfo.replace('\n', ';')
                else:
                    portlist += line                
                    line = bf.readline()        
        else:
            line = bf.readline()
            continue
        line = bf.readline()

    print(info)

def main():
    parser = argparse.ArgumentParser("Need target")
    parser.add_argument("server", type=str)
    args = parser.parse_args()

    nmap_info(args.server)

if __name__ == '__main__':
    main()