#!/usr/bin/env python3

# Skript je součástí webové aplikace Appserver, ale je možné ho využít i jinde.
# Autor:            Václav Šnajdr
# Verze:            1.0
# Rok:              2021
# Diplomová práce:  Webová aplikace pro testování zranitelností webového serveru
# Vysoké učení technické v Brně
# Fakulta elektrotechniky a komunikačních technologií

import os, argparse, io

def hsts_test(server):
    out = ''
    hsts = ''
    # Testuje zda HTTPS hlavička obsahuje zabezpečení HSTS
    out += os.popen('curl -s -D - https://'+server+'/ \
        | grep -i strict').read()
    
    bf = io.StringIO(out)
    line = bf.readline()
    
    if "strict" in line:
        hsts = "HSTS"

    print(hsts)

def main():
    parser = argparse.ArgumentParser("Need target")
    parser.add_argument("server", type=str)
    args = parser.parse_args()

    hsts_test(args.server)

if __name__ == '__main__':
    main()