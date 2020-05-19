echo "[*] Uploading schema"
echo "DROP DATABASE IF EXISTS ideeinbi_epim;" | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim ideeinbi_epim
cat ./database/schema.sql | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim
echo "[+] Done"
echo "[*] Inserting data"
cat ./database/x_istat_data.sql | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim
cat ./database/z_test_data.sql | docker exec -i epim-db /usr/bin/mysql -u epim --password=epim
echo "[+] Done" 
