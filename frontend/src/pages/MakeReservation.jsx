import React, { useState } from "react";
import { Form, DatePicker, Button, Spin, notification } from "antd";

const api_url = import.meta.env.VITE_API_URL;

export default function MakeReservation() {
  const [formBusy, setFormBusy] = useState(false);
  const [form] = Form.useForm();

  const onFormSubmit = async (values) => {
    setFormBusy(true);
    const url = api_url + "/reservation";
    const req = {
      start_date: values["dates"][0].format("YYYY-MM-DD"),
      end_date: values["dates"][1].format("YYYY-MM-DD"),
    };
    const resp = await fetch(url, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify(req),
    });
    const json = await resp.json();
    if (resp.status == 201) {
      notification.open({
        type: "success",
        message: "Reservation Created",
        description: `Id:${json.id}. From ${json.start_date} to ${json.end_date}. Booking money ${json.booking_price}`,
      });
    } else {
      notification.open({
        type: "error",
        message: "Error",
        description: json.message,
      });
    }
    setFormBusy(false);
  };

  return (
    <Spin spinning={formBusy}>
      <Form form={form} onFinish={onFormSubmit}>
        <Form.Item name="dates">
          <DatePicker.RangePicker />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit">
            Make Reservation
          </Button>
        </Form.Item>
      </Form>
    </Spin>
  );
}
