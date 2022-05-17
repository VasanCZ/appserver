#!/usr/bin/env python3

# Skript je součástí webové aplikace Appserver, ale je možné ho využít i jinde.
# Autor:            Václav Šnajdr
# Verze:            1.0
# Rok:              2021
# Diplomová práce:  Webová aplikace pro testování zranitelností webového serveru
# Vysoké učení technické v Brně
# Fakulta elektrotechniky a komunikačních technologií

import os, argparse, io

def ping_test(server):
    out = '' 
    # Testuje se, zda je server dosažitelný, pokud ano, vypíše se řetězec ONLINE
    out += os.popen('ping -c 3 '+server+
    ' < /dev/null > /dev/null 2>&1 && echo ONLINE').read()
    
    print(out)

def main():
    parser = argparse.ArgumentParser("Need target")
    parser.add_argument("server", type=str)
    args = parser.parse_args()

    ping_test(args.server)

if __name__ == '__main__':
    main()