echo "[*] Uploading schema"
cat ./database/schema.sql | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim epim
echo "[+] Done" 
