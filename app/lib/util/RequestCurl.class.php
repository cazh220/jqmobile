<?php

/**
 * CURL ������
 */
class RequestCurl {
	
	//get ����curl
	static public function curl_get($url)
	{
		//��ʼ��
		$curl = curl_init();
		//����ץȡ��url
		curl_setopt($curl, CURLOPT_URL, $url);
		//����ͷ�ļ�����Ϣ��Ϊ���������
		curl_setopt($curl, CURLOPT_HEADER, 1);
		//���û�ȡ����Ϣ���ļ�������ʽ���أ�������ֱ�������
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//ִ������
		$data = curl_exec($curl);
		//�ر�URL����
		curl_close($curl);
		//��ʾ��õ�����
		
		return $data;
	}	
	
	//post ����curl
	static public function curl_post($url, $fields=array())
	{
		//��ʼ��
		$curl = curl_init();
		//����ץȡ��url
		curl_setopt($curl, CURLOPT_URL, $url);
		//����ͷ�ļ�����Ϣ��Ϊ���������
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//���û�ȡ����Ϣ���ļ�������ʽ���أ�������ֱ�������
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//����post��ʽ�ύ
		curl_setopt($curl, CURLOPT_POST, 1);
		//����post����
		curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		//ִ������
		$data = curl_exec($curl);
		//�ر�URL����
		curl_close($curl);
		return $data;
	}
}

?>