echo "[*] Uploading schema"
echo "DROP DATABASE epim;" | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim epim
cat ./database/schema.sql | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim
echo "[+] Done"
echo "[*] Inserting data"
cat ./database/istat_data.sql | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim
echo "[+] Done" 
